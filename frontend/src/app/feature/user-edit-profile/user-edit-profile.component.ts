import {Component, OnDestroy, OnInit} from '@angular/core';
import {DemandFormService} from "../demand-form/demand-form.service";
import {Subscription} from "rxjs";

@Component({
  selector: 'app-user-profile',
  templateUrl: './user-edit-profile.component.html',
  styleUrls: ['./user-edit-profile.component.css']
})
export class UserEditProfileComponent implements OnInit, OnDestroy {
  private subscriptions: Subscription;

  constructor(private demandFormService: DemandFormService
  ) {

  }

  ngOnInit() {
    this.subscriptions = (this.demandFormService.getRoles().subscribe(roles => {
      this.demandFormService.roles = roles;
      console.log(roles);
    }));
  }

  automaticallySendToPlanners($event) {
    this.demandFormService.setAutomaticallySendToPlanners($event.checked);
    // console.log($event.checked)
  }

  ngOnDestroy(): void {
    this.subscriptions.unsubscribe();
  }
}