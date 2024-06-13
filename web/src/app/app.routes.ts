import { Routes } from '@angular/router';

export const routes: Routes = [

    {path:'', redirectTo: 'home', pathMatch: 'full'},
    {path: 'home', loadComponent: () => import('./home/home.component').then(h => h.HomeComponent)},
    {path: 'disponibilidad', loadComponent: () => import('./disponibilidad/disponibilidad.component').then(d => d.DisponibilidadComponent)},

];
