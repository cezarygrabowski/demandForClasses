import {Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import {MatTableDataSource, MatSort} from '@angular/material';
import {DemandElement} from './demand-element';
import {DemandListService} from './demand-list.service';
import {Router} from '@angular/router';
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
    private filteredDemands;
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
        this.filteredDemands = [];
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
        this.demandFormService.exportDemands().subscribe(result => saveAs(result, `export.csv`), error1 => console.log(error1));
    }


    filterBySubject(value: any) {
        if (value === '') {
            this.dataSource = new MatTableDataSource(this.demands);
            this.filteredDemands = [];
        } else {
            const filteredDemands = this.demands.filter((demand: DemandElement) => {
                return demand.subject === value;
            });
            if (this.filteredDemands.length === 0) {
                this.filteredDemands = filteredDemands;
            } else {
                this.filteredDemands = this.filteredDemands.concat(filteredDemands);
            }
            this.dataSource = new MatTableDataSource(this.filteredDemands);
        }
    }

    filterByGroup(value: any) {
        if (value === '') {
            this.dataSource = new MatTableDataSource(this.demands);
            this.filteredDemands = [];
        } else {
            const filteredDemands = this.demands.filter((demand: DemandElement) => {
                return demand.group === value;
            });
            if (this.filteredDemands.length === 0) {
                this.filteredDemands = filteredDemands;
            } else {
                this.filteredDemands = this.filteredDemands.concat(filteredDemands);
            }
            this.dataSource = new MatTableDataSource(this.filteredDemands);
        }
    }
}

import {DemandFormService} from '../demand-form/demand-form.service';
import {saveAs} from 'file-saver';


