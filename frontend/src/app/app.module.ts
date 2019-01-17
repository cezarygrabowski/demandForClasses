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
  MatAutocomplete, MatAutocompleteModule, MatButtonToggle, MatButtonToggleModule
} from '@angular/material';
import { MainNavComponent } from './main-nav/main-nav.component';
import { DemandListComponent } from './demand-list/demand-list.component';
import { DemandFormComponent } from './demand-form/demand-form.component';
import { UserProfileComponent } from './user-profile/user-profile.component';
import {LayoutModule} from '@angular/cdk/layout';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {HttpClientModule, HTTP_INTERCEPTORS} from '@angular/common/http';
import {LoginComponent} from './login';
import {JwtInterceptor} from './_helpers';
import { TeacherImportComponent } from './teacher-import/teacher-import.component';
import { ScheduleImportComponent } from './schedule-import/schedule-import.component';
import { SemesterWeeksComponent } from './semester-weeks/semester-weeks.component';
import { LectureFormComponent } from './lecture-type-form/lecture-form.component';
import { PlacesFormComponent } from './place-form/places-form.component';
import { ScheduleFormComponent } from './schedule-form/schedule-form.component';

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
    ScheduleFormComponent
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
    MatButtonToggleModule
  ],
  providers: [
    { provide: HTTP_INTERCEPTORS, useClass: JwtInterceptor, multi: true },

  ],
  bootstrap: [AppComponent]
})
export class AppModule { }

