import {Component, ViewChild} from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';

@Component({
  selector: 'app-teachers-import',
  templateUrl: './teachers-import.component.html',
  styleUrls: ['./teachers-import.component.css']
})
export class TeachersImportComponent {
  @ViewChild('fileInput') fileInput;
  fileForm = this.fb.group({
    file: ['', Validators.required]
  });

  constructor(private fb: FormBuilder) {}

  onSubmit() {
    let fi = this.fileInput.nativeElement;
    if (fi.files && fi.files[0]) {
      let fileToUpload = fi.files[0];
      this.service
          .upload(fileToUpload)
          .subscribe(() => {
            this.flashMessageService.show('Pomyślnie zaimportowano nauczycieli!', { cssClass: 'alert-success', timeout: 2000 });
          });
    }
  }
}
