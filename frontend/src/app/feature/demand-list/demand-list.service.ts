import { Injectable } from '@angular/core';
import {environment} from "../../../environments/environment.local";
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class DemandListService {

  constructor(private http: HttpClient) { }

  getDemands() {
    return this.http.get(`${environment.apiUrl}/demands`);
  }
}
