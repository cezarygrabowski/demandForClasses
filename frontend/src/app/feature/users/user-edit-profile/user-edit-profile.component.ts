import {Component, OnDestroy, OnInit} from '@angular/core';
import {Subscription} from "rxjs";
import {DemandService} from "../../demands/demand.service";

@Component({
  selector: 'app-user-profile',
  templateUrl: './user-edit-profile.component.html',
  styleUrls: ['./user-edit-profile.component.css']
})
export class UserEditProfileComponent implements OnInit, OnDestroy {
  private subscriptions: Subscription;

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
}
