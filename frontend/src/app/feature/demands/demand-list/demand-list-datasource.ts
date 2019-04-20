import {MatPaginator, MatSort, MatTableDataSource} from '@angular/material';
import {DemandListElement} from '../demandListElement';
import {DemandService} from '../demand.service';

/**
 * Data source for the DemandList view. This class should
 * encapsulate all logic for fetching and manipulating the displayed data
 * (including sorting, pagination, and filtering).
 */
export class DemandListDataSource extends MatTableDataSource<DemandListElement> {
  public data: DemandListElement[] = [];

  constructor(
    public paginator: MatPaginator,
    public sort: MatSort,
    private demandService: DemandService
  ) {
    super();
    this.demandService.getDemands().subscribe((result: DemandListElement[]) => {
      this.data = result;
    });
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
  private getPagedData(data: DemandListElement[]) {
    const startIndex = this.paginator.pageIndex * this.paginator.pageSize;
    return data.splice(startIndex, this.paginator.pageSize);
  }

  /**
   * Sort the data (client-side). If you're using server-side sorting,
   * this would be replaced by requesting the appropriate data from the server.
   */
  private getSortedData(data: DemandListElement[]) {
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

class DemandListDataSourceImpl extends DemandListDataSource {

}

/** Simple sort comparator for example ID/Name columns (for client-side sorting). */
function compare(a, b, isAsc) {
  return (a < b ? -1 : 1) * (isAsc ? 1 : -1);
}
