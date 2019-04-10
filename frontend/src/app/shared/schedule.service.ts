import { Injectable } from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {environment} from '../../environments/environment.local';

@Injectable({
  providedIn: 'root'
})
export class ScheduleService {

  constructor(private http: HttpClient) { }

  upload(fd: FormData) {
    return this.http.post(`${environment.apiUrl}/schedules/import`, fd);
  }
}
