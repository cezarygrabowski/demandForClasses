import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LectureTypeFormComponent } from './lecture-type-form.component';

describe('LectureTypeFormComponent', () => {
  let component: LectureTypeFormComponent;
  let fixture: ComponentFixture<LectureTypeFormComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LectureTypeFormComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LectureTypeFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
