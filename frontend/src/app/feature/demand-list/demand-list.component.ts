import {Component, OnInit, ViewChild} from '@angular/core';
import {MatPaginator, MatSort} from '@angular/material';
import {DemandListDataSource} from './demand-list-datasource';
import {Router} from "@angular/router";
import {DemandService} from "../demands/demand.service";

@Component({
  selector: 'app-demand-list',
  templateUrl: './demand-list.component.html',
  styleUrls: ['./demand-list.component.css']
})
export class DemandListComponent implements OnInit {
  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;
  dataSource: DemandListDataSource;
  /** Columns displayed in the table. Columns IDs can be added, removed, or reordered. */
    displayedColumns = ['subjectName', 'department', 'institute', 'semester', 'schoolYear', 'statusName', 'groupName', 'groupType'];
  // displayedColumns = ['groupType', 'groupName'];

  constructor(
    private router: Router,
    private demandService: DemandService
  ) {
  }

  ngOnInit() {
    this.dataSource = new DemandListDataSource(this.paginator, this.sort, this.demandService);
  }

  rowClicked(id) {
    this.router.navigate([`edytuj-zapotrzebowanie/${id}`]);
  }

  exportDemands() {
    let uuids = [];
    this.demandService.exportDemands(uuids).subscribe();
  }
}
