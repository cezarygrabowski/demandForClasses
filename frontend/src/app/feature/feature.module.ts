import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {
    MatInputModule,
    MatButtonModule,
    MatSelectModule,
    MatRadioModule,
    MatCardModule,
    MatTableModule,
    MatPaginatorModule,
    MatSortModule
} from '@angular/material';
import {ReactiveFormsModule} from '@angular/forms';
import {LecturersListComponent} from './users/lecturers-list/lecturers-list.component';
import {PapaParseModule} from 'ngx-papaparse';

@NgModule({
    declarations: [LecturersListComponent],
    imports: [
        CommonModule,
        MatInputModule,
        MatButtonModule,
        MatSelectModule,
        MatRadioModule,
        MatCardModule,
        ReactiveFormsModule,
        MatTableModule,
        MatPaginatorModule,
        MatSortModule,
        PapaParseModule
    ]
})
export class FeatureModule {
}
