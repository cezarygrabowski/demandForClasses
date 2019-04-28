import {Component, OnDestroy, OnInit} from '@angular/core';
import {Subscription} from "rxjs";
import {DemandService} from "../../demands/demand.service";
import {MatCheckboxChange} from "@angular/material";

@Component({
    selector: 'app-user-profile',
    templateUrl: './user-edit-profile.component.html',
    styleUrls: ['./user-edit-profile.component.css']
})
export class UserEditProfileComponent implements OnInit, OnDestroy {
    private subscriptions: Subscription;
    private profilePayload: {} = {};

    constructor(private demandService: DemandService
    ) {

    }

    ngOnInit() {
        // this.subscriptions = (this.demandService.getRoles().subscribe(roles => {
        //   this.demandService.roles = roles;
        //   console.log(roles);
        // }));
    }

    // automaticallySendToPlanners($event) {
    //   this.demandService.setAutomaticallySendToPlanners($event.checked);
    //   // console.log($event.checked)
    // }

    ngOnDestroy(): void {
        // this.subscriptions.unsubscribe();
    }

    changeEmail($event: Event) {
        this.modifyPayload('email', $event.target.value);
    }

    automaticallySendToPlanners($event: Event | MatCheckboxChange) {
        this.modifyPayload('automaticallySendDemands', $event.checked);
    }

    modifyPayload(key: string, value: any) {
        this.profilePayload[key] = value;
    }

    onSubmit() {
        
    }
}
