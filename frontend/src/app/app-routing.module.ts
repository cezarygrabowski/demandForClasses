import { Routes, RouterModule } from '@angular/router';

import { LoginComponent } from './login';
import { AuthGuard } from './_guards';
import {DemandListComponent} from './demand-list/demand-list.component';
import {ScheduleImportComponent} from './schedule-import/schedule-import.component';
import {TeacherImportComponent} from './teacher-import/teacher-import.component';
import {UserProfileComponent} from './user-profile/user-profile.component';
import {DemandFormComponent} from './demand-form/demand-form.component';

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
    component: UserProfileComponent
  },
  {
    path: 'importuj-plany-zajec',
    component: ScheduleImportComponent
  },
  {
    path: 'importuj-nauczycieli',
    component: TeacherImportComponent
  },
  // otherwise redirect to home
  { path: '**', redirectTo: 'zapotrzebowania' }
];

export const AppRoutingModule = RouterModule.forRoot(appRoutes);
