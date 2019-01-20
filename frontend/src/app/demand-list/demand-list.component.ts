import {Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import {MatTableDataSource, MatSort} from '@angular/material';
import {DemandElement} from './demand-element';
import {DemandListService} from './demand-list.service';
import {Router} from '@angular/router';
import {DemandFormService} from "../demand-form/demand-form.service";

@Component({
    selector: 'app-demand-list',
    templateUrl: './demand-list.component.html',
    styleUrls: ['./demand-list.component.css']
})
export class DemandListComponent implements OnInit {
    dataSource;
    displayedColumns = [];
    @ViewChild(MatSort) sort: MatSort;

  private demands;
    columnNames = [
        {id: 'id', value: 'Numer zapotrzebowania'},
        {id: 'subject', value: 'Przedmiot'},
        {id: 'hours', value: 'Godzin razem'},
        {id: 'group', value: 'Grupa'},
        {id: 'groupType', value: 'Rodzaj grupy'},
        {id: 'status', value: 'Status'}
    ];

    constructor(
        private demandListService: DemandListService,
        private router: Router,
        private demandFormService: DemandFormService) {
    }

    ngOnInit() {
        this.displayedColumns = this.columnNames.map(x => x.id);
        this.demandListService.getDemands().subscribe(result => {
            this.demands = result;
            this.createTable();
        });
    }

    rowClicked(id) {
        this.router.navigate([`edytuj-zapotrzebowanie/${id}`]);
    }

    createTable() {
        const tableArr: DemandElement[] = this.demands;
        this.dataSource = new MatTableDataSource(tableArr);
        this.dataSource.sort = this.sort;
    }

  exportDemands() {
    this.demandFormService.exportDemands().subscribe();
  }
}


