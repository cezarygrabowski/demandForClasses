import { Routes, RouterModule } from '@angular/router';

import { LoginComponent } from './feature/users/login';
import { AuthGuard } from './shared/_guards';
import {DemandFormComponent} from './feature/demands/demand-form/demand-form.component';
import {TeachersImportComponent} from './feature/users/teachers-import/teachers-import.component';
import {ScheduleImportComponent} from './feature/demands/schedule-import/schedule-import.component';
import {DemandListComponent} from './feature/demands/demand-list/demand-list.component';
import {UserEditProfileComponent} from './feature/users/user-edit-profile/user-edit-profile.component';
import {LecturersListComponent} from "./feature/users/lecturers-list/lecturers-list.component";

const appRoutes: Routes = [
  {
    path: 'zapotrzebowania',
    component: DemandListComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'prowadzacy',
    component: LecturersListComponent,
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
