import { Component, OnInit, ViewChild } from '@angular/core';
import { MatPaginator, MatSort } from '@angular/material';
import { UsersListDataSource } from './users-list-datasource';
import {UserService} from '../user.service';

@Component({
  selector: 'app-users-list',
  templateUrl: './lecturers-list.component.html',
  styleUrls: ['./lecturers-list.component.css']
})
export class LecturersListComponent implements OnInit {
  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;
  dataSource: UsersListDataSource;

  /** Columns displayed in the table. Columns IDs can be added, removed, or reordered. */
  displayedColumns = ['username', 'qualifications', 'roles'];

  constructor(
      private userService: UserService
  ) {}

  ngOnInit() {
    this.dataSource = new UsersListDataSource(this.paginator, this.sort, this.userService);
  }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }
}
