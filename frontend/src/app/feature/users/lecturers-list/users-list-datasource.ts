import {MatPaginator, MatSort, MatTableDataSource} from '@angular/material';
import {UserService} from '../user.service';

// TODO: Replace this with your own data model type
export interface UsersListItem {
    username: string;
    qualifications: string;
    roles: string;
}

/**
 * Data source for the UsersList view. This class should
 * encapsulate all logic for fetching and manipulating the displayed data
 * (including sorting, pagination, and filtering).
 */
export class UsersListDataSource extends MatTableDataSource<UsersListItem> {
    public data: UsersListItem[] = [];

    constructor(
        public paginator: MatPaginator,
        public sort: MatSort,
        private userService: UserService
    ) {
        super();
        this.userService.getAll().subscribe((result: UsersListItem[]) => {
            this.data = result;
        });
    }

    /**
     *  Called when the table is being destroyed. Use this function, to clean up
     * any open connections or free any held resources that were set up during connect.
     */
    disconnect() {
    }

    /**
     * Paginate the data (client-side). If you're using server-side pagination,
     * this would be replaced by requesting the appropriate data from the server.
     */
    private getPagedData(data: UsersListItem[]) {
        const startIndex = this.paginator.pageIndex * this.paginator.pageSize;
        return data.splice(startIndex, this.paginator.pageSize);
    }

    /**
     * Sort the data (client-side). If you're using server-side sorting,
     * this would be replaced by requesting the appropriate data from the server.
     */
    private getSortedData(data: UsersListItem[]) {
        if (!this.sort.active || this.sort.direction === '') {
            return data;
        }

        return data.sort((a, b) => {
            const isAsc = this.sort.direction === 'asc';
            switch (this.sort.active) {
                case 'username': return compare(a.username, b.username, isAsc);
                case 'qualifications': return compare(+a.qualifications, +b.qualifications, isAsc);
                case 'roles': return compare(+a.roles, +b.roles, isAsc);
                default:
                    return 0;
            }
        });
    }
}

/** Simple sort comparator for example ID/Name columns (for client-side sorting). */
function compare(a, b, isAsc) {
    return (a < b ? -1 : 1) * (isAsc ? 1 : -1);
}
