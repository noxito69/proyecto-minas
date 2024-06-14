import { Component } from '@angular/core';
import { LayoutComponent } from '../layout/layout.component';
import { GoogleChartInterface, Ng2GoogleChartsModule } from 'ng2-google-charts';
import { HttpClient, HttpClientModule } from '@angular/common/http';

interface EquipmentData {
  category: string;
  class: string;
  equipment_no: string;
  operating_time: number;
}

@Component({
  selector: 'app-disponibilidad',
  standalone: true,
  imports: [LayoutComponent,Ng2GoogleChartsModule,HttpClientModule],
  templateUrl: './disponibilidad.component.html',
  styleUrl: './disponibilidad.component.css'
})
export class DisponibilidadComponent {
  equipmentData: EquipmentData[] = [];

  ngOnInit() {

    this.getData();
  }

  constructor(private http: HttpClient) { }


  getData() {
    this.http.get<EquipmentData[]>('http://localhost:8000/api/fleet/indexShovel').subscribe(data => {
      this.equipmentData = data;
      this.updatePieChart();
    });
  }

  updatePieChart() {
    this.pieChart.dataTable = [['Equipment', 'Operating Time', 'Class'], 
      ...this.equipmentData.map(item => [item.equipment_no, item.operating_time, item.class])];
    this.pieChart.options = { title:'Tiempo de operacion de las palas' };
    if (this.pieChart.component) {
      this.pieChart.component.draw();
    }
  }


  public pieChart: GoogleChartInterface = {
    chartType: 'PieChart',
    dataTable: [
      ['Eqipment', 'Operating Time', 'Class'],
   
    ],
    options: {

      
      title: 'How Much Pizza I Ate Last Night',
      width: 1000, // Ancho del gráfico
      height: 1000, // Altura del gráfico
      is3D: true, // Hacer que el gráfico sea 3D
      colors: ['#e0440e', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6'], // Colores de las rebanadas
    },
  };



}
