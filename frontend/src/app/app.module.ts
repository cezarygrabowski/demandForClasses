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
  MatAutocompleteModule, MatButtonToggleModule, MatRadioModule
} from '@angular/material';
import { MainNavComponent } from './core/main-nav/main-nav.component';
import { DemandListComponent } from './feature/demand-list/demand-list.component';
import { DemandFormComponent } from './feature/demand-form/demand-form.component';
import { UserProfileComponent } from './feature/user-profile/user-profile.component';
import {LayoutModule} from '@angular/cdk/layout';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {HttpClientModule, HTTP_INTERCEPTORS} from '@angular/common/http';
import {LoginComponent} from './feature/login';
import {JwtInterceptor} from './shared/_helpers';
import { TeacherImportComponent } from './feature/teacher-importd/teacher-import.component';
import { ScheduleImportComponent } from './feature/schedule-importd/schedule-import.component';
import { SemesterWeeksComponent } from './feature/semester-weeks/semester-weeks.component';
import { LectureFormComponent } from './feature/lecture-type-form/lecture-form.component';
import { PlacesFormComponent } from './feature/place-form/places-form.component';
import { ScheduleFormComponent } from './feature/schedule-form/schedule-form.component';
import {FlashMessagesModule} from 'angular2-flash-messages';
import { TeachersImportComponent } from './feature/teachers-import/teachers-import.component';
import {MaterialFileInputModule} from "ngx-material-file-input";

@NgModule({
  declarations: [
    AppComponent,
    MainNavComponent,
    DemandListComponent,
    DemandFormComponent,
    UserProfileComponent,
    LoginComponent,
    TeacherImportComponent,
    ScheduleImportComponent,
    SemesterWeeksComponent,
    LectureFormComponent,
    PlacesFormComponent,
    ScheduleFormComponent,
    TeachersImportComponent
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
    MaterialFileInputModule
  ],
  providers: [
    { provide: HTTP_INTERCEPTORS, useClass: JwtInterceptor, multi: true },
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }

