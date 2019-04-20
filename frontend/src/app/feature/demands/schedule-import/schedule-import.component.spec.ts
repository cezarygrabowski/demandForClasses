import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ScheduleImportComponent } from './schedule-import.component';

describe('ScheduleImportComponent', () => {
  let component: ScheduleImportComponent;
  let fixture: ComponentFixture<ScheduleImportComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ScheduleImportComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ScheduleImportComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
