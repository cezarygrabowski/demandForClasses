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
import {LecturersListComponent} from "./users/lecturers-list/lecturers-list.component";

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
        MatSortModule
    ]
})
export class FeatureModule {
}
