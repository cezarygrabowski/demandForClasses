import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {environment} from "../../environments/environment.local";

@Injectable({
  providedIn: 'root'
})
export class TeacherService {

  constructor(private http: HttpClient) { }

  upload(fd: FormData) {
    return this.http.post(`${environment.apiUrl}/import-lecturers`, fd);
  }
}
