import {Component, OnDestroy, OnInit} from '@angular/core';

@Component({
    selector: 'app-demand-form',
    templateUrl: './demand-form.component.html',
    styleUrls: ['./demand-form.component.css']
})
export class DemandFormComponent implements OnInit, OnDestroy {
    ngOnDestroy(): void {
    }

    ngOnInit(): void {
    }
  // @ViewChild('demandContent') demandContent: ElementRef;
  // submitted = false;
  // demandElement: DemandListElement;
  // private subscriptions: Subscription;
  // private qualifiedLecturers: Object;
  // private buildings: Building[];
  // private lectures: Lecture[];
  // private currentlyLoggedUserRoles: Object;
  //
  //   constructor(
  //       private route: ActivatedRoute,
  //       private router: Router,
  //       private demandFormService: DemandFormService,
  //   ) {
  //   }
  //
  //   ngOnInit() {
  //       const id = this.route.snapshot.paramMap.get('id');
  //       this.lectures = [];
  //
  //       this.subscriptions = new Subscription();
  //       this.subscriptions.add(this.getDemandDetails(id).subscribe(res => {
  //           this.demandElement = res;
  //       }));
  //
  //       this.subscriptions.add(this.getQualifiedLecturers(id).subscribe(res => {
  //           this.qualifiedLecturers = res;
  //       }));
  //
  //       this.subscriptions.add(this.demandFormService.getBuildings().subscribe(res => {
  //           this.buildings = res;
  //       }));
  //       this.subscriptions.add(this.demandFormService.getRoles().subscribe(roles => {
  //           this.demandFormService.roles = roles;
  //       }));
  //   }
  //
  //   onSubmit() {
  //       if (this.lectures.length > 0) {
  //           this.demandElement.lectures = this.lectures;
  //       }
  //
  //       this.demandFormService.updateDemand(this.demandElement);
  //   }
  //
  //   private getDemandDetails(id: string): Observable<DemandListElement> {
  //       return this.demandFormService.getDemandDetails(id);
  //   }
  //
  //   ngOnDestroy(): void {
  //       this.subscriptions.unsubscribe();
  //   }
  //
  //   private getQualifiedLecturers(id) {
  //       return this.demandFormService.getLecturers(id);
  //   }
  //
  //   onEmittedLecture(lecture: Lecture) {
  //       if (this.lectureExists(lecture.lectureType.id)) {
  //           this.removeLectureFromArray(lecture.lectureType.id);
  //       }
  //
  //       this.lectures.push(lecture);
  //   }
  //
  //   lectureExists(id: number): boolean {
  //       return this.lectures.some((lecture: Lecture) => lecture.lectureType.id === id);
  //   }
  //
  //   removeLectureFromArray(id: number) {
  //       this.lectures.forEach( (lecture: Lecture, index) => {
  //           if (lecture.lectureType.id === id) { this.lectures.splice(index, 1); }
  //       });
  //   }
  //
  // showPdf() {
  //   var printWindow = window.open('', '', 'height=400,width=800');
  //   printWindow.document.write('<html><head><title>DIV Contents</title>');
  //   printWindow.document.write('</head><body >');
  //   printWindow.document.write(this.demandContent.toString());
  //   printWindow.document.write('</body></html>');
  //   printWindow.document.close();
  //   printWindow.print();
  // }
  //
  //   onCancel() {
  //       this.demandFormService.cancelDemand(this.demandElement);
  //   }
}
