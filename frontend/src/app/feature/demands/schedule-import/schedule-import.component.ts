import { Component } from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {FlashMessagesService} from 'angular2-flash-messages';
import {ScheduleService} from '../../../shared/schedule.service';

@Component({
  selector: 'app-schedule-import',
  templateUrl: './schedule-import.component.html',
  styleUrls: ['./schedule-import.component.css']
})
export class ScheduleImportComponent {
  form: FormGroup = this.fb.group({
    requiredFile: [
      undefined,
      [Validators.required]
    ]
  });
  constructor(
      private service: ScheduleService,
      private flashMessageService: FlashMessagesService,
      private fb: FormBuilder
  ) {
  }

  onSubmit() {
    const fileToUpload = this.form.get('requiredFile').value.files[0];
    const fd = new FormData();
    fd.append('file', fileToUpload);
    this.service
        .upload(fd)
        .subscribe(() => {
              this.flashMessageService.show('Pomyślnie zaimportowano plany zajęć!');
            }, error1 => this.flashMessageService.show(error1)
        );
  }
}
