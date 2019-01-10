import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SemesterWeeksComponent } from './semester-weeks.component';

describe('SemesterWeeksComponent', () => {
  let component: SemesterWeeksComponent;
  let fixture: ComponentFixture<SemesterWeeksComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SemesterWeeksComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SemesterWeeksComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
