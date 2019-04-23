import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {FlashMessagesService} from 'angular2-flash-messages';
import {FileValidator} from 'ngx-material-file-input';
import {UserService} from "../user.service";

@Component({
    selector: 'app-teachers-import',
    templateUrl: './teachers-import.component.html',
    styleUrls: ['./teachers-import.component.css']
})
export class TeachersImportComponent implements OnInit {
    form: FormGroup;
    private maxSize = 10485760;
    constructor(
        private service: UserService,
        private flashMessageService: FlashMessagesService,
        private fb: FormBuilder
    ) {
    }

    ngOnInit() {
        this.form = this.fb.group({
            requiredFile: [
                undefined,
                [Validators.required, FileValidator.maxContentSize(this.maxSize)]
            ]
        });
    }

    onSubmit() {
        const fileToUpload = this.form.get('requiredFile').value.files[0];
        const fd = new FormData();
        fd.append('file', fileToUpload);
        this.service
            .upload(fd)
            .subscribe(() => {
                this.flashMessageService.show('Pomyślnie zaimportowano nauczycieli!');
            }, (error1) => {
                this.flashMessageService.show(error1);
              }
            );
    }
}
