import { TestBed } from '@angular/core/testing';

import { DemandListService } from './demand-list.service';

describe('DemandFormService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: DemandListService = TestBed.get(DemandListService);
    expect(service).toBeTruthy();
  });
});
