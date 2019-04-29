import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {UserService} from '../user.service';
import {UserDetails} from "../interfaces/user-details";

@Component({
    selector: 'app-user-profile',
    templateUrl: './user-edit-profile.component.html',
    styleUrls: ['./user-edit-profile.component.css']
})
export class UserEditProfileComponent implements OnInit, OnDestroy {
    private profileForm: FormGroup;

    constructor(private userService: UserService
    ) {
        this.userService.getProfile().subscribe((userDetails: UserDetails) => {
            console.log(userDetails);
            this.profileForm = new FormGroup({
                email: new FormControl(userDetails.email, Validators.email),
                automaticallySendDemands: new FormControl(userDetails.automaticallySendDemands),
            });
        });
    }

    ngOnInit() {
    }

    ngOnDestroy(): void {
    }

    onSubmit() {
        this.userService.updateProfile(this.profileForm.getRawValue()).subscribe();
    }
}
