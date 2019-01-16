import { Injectable } from '@angular/core';
import {environment} from "../../environments/environment.local";
import {HttpClient} from "@angular/common/http";
import {DemandElement} from "../demand-list/demand-element";
import {Observable} from "rxjs";

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

  getBuildings(): Observable<any> {
    return this.http.get(`${environment.apiUrl}/demands/buildings`);
  }

  updateDemand(demand: DemandElement): Observable<any> {
    return this.http.post<any>(`${environment.apiUrl}/demands/update/${demand.id}`, demand);
  }

  apiRequest(demand: DemandElement): Promise<any> {
    // this.http.post<any>(`${environment.apiUrl}/demands/update/${demand.id}`, demand).subscribe(response => console.log(response.json()));
    return this.http.post<any>(`${environment.apiUrl}/api`, demand).toPromise()
        .then(value => console.log(value))
        .catch(error => console.log(error));
  }

}
