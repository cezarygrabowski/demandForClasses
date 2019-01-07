import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {ActivatedRoute} from '@angular/router';
import {DemandFormService} from './demand-form.service';
import {Subscription} from 'rxjs';

@Component({
  selector: 'app-demand-form',
  templateUrl: './demand-form.component.html',
  styleUrls: ['./demand-form.component.css']
})
export class DemandFormComponent implements OnInit, OnDestroy {
  registerForm: FormGroup;
  submitted = false;
  private demandElement: Object;
  private subscriptions: Subscription;
  private qualifiedLecturers: Object;
  constructor(
      private formBuilder: FormBuilder,
      private route: ActivatedRoute,
      private demandFormService: DemandFormService
  ) { }

  ngOnInit() {
    const id = this.route.snapshot.paramMap.get('id');
    this.subscriptions = this.getQualifiedLecturers(id).subscribe(res => {
      this.qualifiedLecturers = res;
    });

    const demandSubscription = this.getDemandDetails(id).subscribe(res => {
      this.demandElement = res;
    });
    this.subscriptions.add(demandSubscription);

    this.registerForm = this.formBuilder.group({
      lecturer: ['', Validators.required],
    });
  }

  // convenience getter for easy access to form fields
  get f() { return this.registerForm.controls; }

  onSubmit() {
    this.submitted = true;

    // stop here if form is invalid
    if (this.registerForm.invalid) {
      return;
    }

    alert('SUCCESS!! :-)\n\n' + JSON.stringify(this.registerForm.value));
  }

  private getDemandDetails(id: string) {
    return this.demandFormService.getDemandDetails(id);
  }


  ngOnDestroy(): void {
    this.subscriptions.unsubscribe();
  }

  private getQualifiedLecturers(id) {
    return this.demandFormService.getLecturers(id);
  }
}
