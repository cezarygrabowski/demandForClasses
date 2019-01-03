import { Component, OnInit, ViewChild } from '@angular/core';
import { MatTableDataSource, MatSort } from '@angular/material';
import { DemandElement } from './demand-element';

@Component({
  selector: 'app-demand-list',
  templateUrl: './demand-list.component.html',
  styleUrls: ['./demand-list.component.css']
})
export class DemandListComponent implements OnInit {

  dataSource;
  displayedColumns = [];
  @ViewChild(MatSort) sort: MatSort;

  columnNames = [
    {id: 'subject', value: 'Przedmiot'},
    {id: 'hours', value: 'Godziny'},
    {id: 'blocks', value: 'Bloki'},
    {id: 'comments', value: 'Uwagi'},
    {id: 'status', value: 'Status'},
    {id: 'building', value: 'Building'},
    {id: 'room', value: 'Sala'},
  ];

  ngOnInit() {
    this.displayedColumns = this.columnNames.map(x => x.id);
    this.createTable();
  }

  createTable() {
    const tableArr: DemandElement[] = [
      { subject: 'Informayka', hours: 40, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 40, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 40, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 40, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 33, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 40, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 40, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 55, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 40, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 40, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 40, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Wychowanie Fizyczne', hours: 66, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 40, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 40, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 40, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 40, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 40, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 40, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 40, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
      { subject: 'Informayka', hours: 40, blocks: '0,4,4,4,2,2,4,4,4,4,4,4,-,-,-,-', comments: 'Ostatnie zajęcia proszę planować w sali laboratoryjnej', status: 'Zaplanowane', building: 65, room: 105},
    ];
    this.dataSource = new MatTableDataSource(tableArr);
    this.dataSource.sort = this.sort;
  }
}


