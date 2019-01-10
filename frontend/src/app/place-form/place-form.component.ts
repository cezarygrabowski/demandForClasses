import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {Week} from '../_models/week';
import {Building} from '../_interfaces/building';
import {FormBuilder, FormGroup} from '@angular/forms';
import {Room} from '../_interfaces/room';
import {Schedule} from '../_models/schedule';

@Component({
    selector: 'app-place-form',
    templateUrl: './place-form.component.html',
    styleUrls: ['./place-form.component.css']
})
export class PlaceFormComponent implements OnInit {
    weekLabel: string;
    building: Building;
    room: Room;
    placeForm: FormGroup;

    @Input('week') week: Week;
    @Input('buildings') buildings: Building[];

    @Output() schedulesEmitter: EventEmitter<Schedule[]> = new EventEmitter();
    @Output() buildingEmitter: EventEmitter<string>;
    @Output() roomEmitter: EventEmitter<string>;

    constructor(private formBuilder: FormBuilder) {}

    ngOnInit() {
        this.building = null;
        this.room = null;
        this.initForm();
        this.weekLabel = Week.WEEK_TRANSLATION[this.week.week];
    }

    private initForm() {
        this.placeForm = this.formBuilder.group({
            building: [''],
            room: [''],
        });

        this.onChanges();
    }

    private onChanges() {
        this.placeForm.get('building').valueChanges.subscribe((buildingNumber: string) => {
            this.getBuildingFromForm(buildingNumber);
            this.placeForm.get('room').setValue(null);
            this.emitSchedule();

        });

        this.placeForm.get('room').valueChanges.subscribe((roomNumber: string) => {
            this.getRoomFromForm(roomNumber);
            this.emitSchedule();
        });
    }

    private getBuildingFromForm(buildingNumber: string): void {
        this.building = this.buildings.filter((building: Building) => {
            return building.name === buildingNumber;
        })[0];
    }

    private getRoomFromForm(roomNumber: string): void {
        this.room = this.building.rooms.filter((room: Room) => {
            return room.name === roomNumber;
        })[0];
    }

    private renderScheduleForEachWeek() //TODO go!
    {

    }

    private emitSchedules()
    {
        const weekNumber = this.week.week.replace( /^\D+/g, '');
        console.log(weekNumber);
        // najpierw render schedule for eachw week
        // this.schedulesEmitter.emit(new Schedule(null, weekNumber, this.week.hours, this.building ? this.building.id : null, this.room ? this.room.id : null));
    }
}
