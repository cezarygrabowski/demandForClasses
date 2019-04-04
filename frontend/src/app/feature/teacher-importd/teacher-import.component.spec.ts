import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TeacherImportComponent } from './teacher-import.component';

describe('TeacherImportComponent', () => {
  let component: TeacherImportComponent;
  let fixture: ComponentFixture<TeacherImportComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TeacherImportComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TeacherImportComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
