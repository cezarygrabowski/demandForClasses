import { DataSource } from '@angular/cdk/collections';
import { MatPaginator, MatSort } from '@angular/material';
import { map } from 'rxjs/operators';
import { Observable, of as observableOf, merge } from 'rxjs';
import {Demand} from "./demand";
import {DemandService} from "../demands/demand.service";

/**
 * Data source for the DemandList view. This class should
 * encapsulate all logic for fetching and manipulating the displayed data
 * (including sorting, pagination, and filtering).
 */
export class DemandListDataSource extends DataSource<Demand> {
  private data: Demand[] = [];

  constructor(
    private paginator: MatPaginator,
    private sort: MatSort,
    private demandService: DemandService
  ) {
    super();
    this.demandService.getDemands().subscribe((result: Demand[]) => {
      this.data = result;
    });
  }

  /**
   * Connect this data source to the table. The table will only update when
   * the returned stream emits new items.
   * @returns A stream of the items to be rendered.
   */
  connect(): Observable<Demand[]> {
    // Combine everything that affects the rendered data into one update
    // stream for the data-table to consume.
    const dataMutations = [
      observableOf(this.data),
      this.paginator.page,
      this.sort.sortChange
    ];

    // Set the paginator's length
    this.paginator.length = this.data.length;

    return merge(...dataMutations).pipe(map(() => {
      return this.getPagedData(this.getSortedData([...this.data]));
    }));
  }

  /**
   *  Called when the table is being destroyed. Use this function, to clean up
   * any open connections or free any held resources that were set up during connect.
   */
  disconnect() {}

  /**
   * Paginate the data (client-side). If you're using server-side pagination,
   * this would be replaced by requesting the appropriate data from the server.
   */
  private getPagedData(data: Demand[]) {
    const startIndex = this.paginator.pageIndex * this.paginator.pageSize;
    return data.splice(startIndex, this.paginator.pageSize);
  }

  /**
   * Sort the data (client-side). If you're using server-side sorting,
   * this would be replaced by requesting the appropriate data from the server.
   */
  private getSortedData(data: Demand[]) {
    if (!this.sort.active || this.sort.direction === '') {
      return data;
    }

    return data.sort((a, b) => {
      const isAsc = this.sort.direction === 'asc';
      switch (this.sort.active) {
        case 'subjectName': return compare(a.subjectName, b.subjectName, isAsc);
        case 'department': return compare(+a.department, +b.department, isAsc);
        case 'institute': return compare(+a.institute, +b.institute, isAsc);
        case 'semester': return compare(+a.semester, +b.semester, isAsc);
        case 'schoolYear': return compare(+a.schoolYear, +b.schoolYear, isAsc);
        case 'statusName': return compare(+a.statusName, +b.statusName, isAsc);
        case 'groupName': return compare(+a.groupName, +b.groupName, isAsc);
        case 'groupType': return compare(+a.groupType, +b.groupType, isAsc);
        default: return 0;
      }
    });
  }
}

/** Simple sort comparator for example ID/Name columns (for client-side sorting). */
function compare(a, b, isAsc) {
  return (a < b ? -1 : 1) * (isAsc ? 1 : -1);
}
