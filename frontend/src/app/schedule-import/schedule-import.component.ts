import {Component, OnInit, ViewChild} from '@angular/core';
import {UploadService} from './upload.service';

@Component({
  selector: 'app-schedule-import',
  templateUrl: './schedule-import.component.html',
  styleUrls: ['./schedule-import.component.css']
})
export class ScheduleImportComponent implements OnInit {
  @ViewChild('fileInput') fileInput;
  constructor(private service: UploadService) { }

  ngOnInit() {
  }

  addFile(): void {
    let fi = this.fileInput.nativeElement;
    if (fi.files && fi.files[0]) {
      let fileToUpload = fi.files[0];
      this.service
          .upload(fileToUpload)
          .subscribe(res => {
            console.log(res);
          });
    }
  }
}
