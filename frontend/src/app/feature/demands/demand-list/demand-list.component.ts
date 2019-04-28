import {Component, OnInit, ViewChild} from '@angular/core';
import {MatPaginator, MatSort} from '@angular/material';
import {DemandListDataSource} from './demand-list-datasource';
import {Router} from "@angular/router";
import {DemandService} from "../demand.service";
import {Demand} from "../demand";
import {DemandListElement} from "../demandListElement";
import {AuthenticationService} from "../../../shared/_services";

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
    private demandService: DemandService,
    private authenticationService: AuthenticationService
  ) {
  }

  ngOnInit() {
    this.dataSource = new DemandListDataSource(this.paginator, this.sort, this.demandService);
  }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  rowClicked(id) {
    this.router.navigate([`edytuj-zapotrzebowanie/${id}`]);
  }

  exportDemands() {
    const uuids = [];
    this.dataSource.filteredData.map((demand: DemandListElement) => {
      uuids.push(demand.uuid);
    });

    this.demandService.exportDemands(uuids).subscribe(result => {
      // console.log(result);
      this.downloadFile(result);
    });
  }

  downloadFile(blob: any) {
    saveAs(blob, 'myFile.csv');
  }
}
