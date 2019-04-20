import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import {
  MatButtonModule,
  MatCheckboxModule,
  MatToolbarModule,
  MatSidenavModule,
  MatIconModule,
  MatListModule,
  MatTableModule,
  MatSortModule,
  MatSelectModule,
  MatInputModule,
  MatMenuModule,
  MatCardModule,
  MatProgressSpinnerModule,
  MatFormFieldModule,
  MatAutocompleteModule, MatButtonToggleModule, MatRadioModule, MatPaginatorModule
} from '@angular/material';
import { MainNavComponent } from './core/main-nav/main-nav.component';
import { DemandListComponent } from './feature/demands/demand-list/demand-list.component';
import { DemandFormComponent } from './feature/demands/demand-form/demand-form.component';
import {LayoutModule} from '@angular/cdk/layout';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {HttpClientModule, HTTP_INTERCEPTORS} from '@angular/common/http';
import {LoginComponent} from './feature/users/login';
import {JwtInterceptor} from './shared/_helpers';
import { SemesterWeeksComponent } from './feature/demands/semester-weeks/semester-weeks.component';
import { LectureFormComponent } from './feature/demands/lecture-type-form/lecture-form.component';
import { PlacesFormComponent } from './feature/demands/place-form/places-form.component';
import { ScheduleFormComponent } from './feature/demands/schedule-form/schedule-form.component';
import {FlashMessagesModule} from 'angular2-flash-messages';
import { TeachersImportComponent } from './feature/users/teachers-import/teachers-import.component';
import {MaterialFileInputModule} from 'ngx-material-file-input';
import {UserEditProfileComponent} from "./feature/users/user-edit-profile/user-edit-profile.component";
import {ScheduleImportComponent} from "./feature/demands/schedule-import/schedule-import.component";
import {FeatureModule} from "./feature/feature.module";
import {SharedModule} from "./shared/shared.module";
import {CoreModule} from "./core/core.module";
import {ScheduleService} from "./shared/schedule.service";
import {DemandService} from "./feature/demands/demand.service";

@NgModule({
  declarations: [
    AppComponent,
    MainNavComponent,
    DemandListComponent,
    DemandFormComponent,
    UserEditProfileComponent,
    LoginComponent,
    SemesterWeeksComponent,
    LectureFormComponent,
    PlacesFormComponent,
    ScheduleFormComponent,
    TeachersImportComponent,
    ScheduleImportComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    MatButtonModule,
    MatCheckboxModule,
    LayoutModule,
    MatToolbarModule,
    MatSidenavModule,
    MatIconModule,
    MatListModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    MatCardModule,
    MatProgressSpinnerModule,
    MatMenuModule,
    MatFormFieldModule,
    MatInputModule,
    MatSelectModule,
    MatSortModule,
    MatTableModule,
    MatButtonModule,
    MatAutocompleteModule,
    MatButtonToggleModule,
    FlashMessagesModule.forRoot(),
    MatRadioModule,
    MaterialFileInputModule,
    FeatureModule,
    SharedModule,
    CoreModule,
    MatPaginatorModule
  ],
  providers: [
    { provide: HTTP_INTERCEPTORS, useClass: JwtInterceptor, multi: true },
    ScheduleService,
    DemandService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }

