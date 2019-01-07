import { Injectable } from '@angular/core';
import {environment} from "../../environments/environment.local";
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class DemandFormService {

  constructor(private http: HttpClient) { }

  getDemandDetails(id: string) {
    return this.http.get(`${environment.apiUrl}/demands/details/${id}`);
  }

  getLecturers(demandId: string) {
    return this.http.get(`${environment.apiUrl}/lecturers/${demandId}`);
  }
}
