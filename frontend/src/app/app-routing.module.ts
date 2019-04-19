import { Routes, RouterModule } from '@angular/router';

import { LoginComponent } from './feature/login';
import { AuthGuard } from './shared/_guards';
import {DemandFormComponent} from './feature/demand-form/demand-form.component';
import {TeachersImportComponent} from './feature/teachers-import/teachers-import.component';
import {ScheduleImportComponent} from './feature/schedule-import/schedule-import.component';
import {UserEditProfileComponent} from "./feature/user-edit-profile/user-edit-profile.component";
import {DemandListComponent} from "./feature/demand-list/demand-list.component";

const appRoutes: Routes = [
  {
    path: 'zapotrzebowania',
    component: DemandListComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'edytuj-zapotrzebowanie/:id',
    component: DemandFormComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'login',
    component: LoginComponent
  },
  {
    path: 'profil',
    component: UserEditProfileComponent
  },
  {
    path: 'importuj-plany-zajec',
    component: ScheduleImportComponent
  },
  {
    path: 'importuj-nauczycieli',
    component: TeachersImportComponent
  },
  // otherwise redirect to home
  { path: '**', redirectTo: 'zapotrzebowania' }
];

export const AppRoutingModule = RouterModule.forRoot(appRoutes);
