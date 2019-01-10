import { Injectable } from '@angular/core';
import {environment} from "../../environments/environment.local";
import {HttpClient} from "@angular/common/http";
import {DemandElement} from "../demand-list/demand-element";
import {Observable} from "rxjs";
import {Building} from "../_interfaces/building";

@Injectable({
  providedIn: 'root'
})
export class DemandFormService {

  constructor(private http: HttpClient) { }

  getDemandDetails(id: string): Observable<DemandElement>
  {
    return this.http.get<DemandElement>(`${environment.apiUrl}/demands/details/${id}`);
  }

  getLecturers(demandId: string) {
    return this.http.get(`${environment.apiUrl}/lecturers/${demandId}`);
  }

  getBuildings() {
    return this.http.get(`${environment.apiUrl}/demands/buildings`);
  }
}
