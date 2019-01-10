export class Lecturer {
    id: number;
    username: string;
    qualifications: [];
    constructor(
        id: number,
        username: string,
        qualifiactions: []
    ) {
        this.id = id;
        this.username = username;
        this.qualifications = qualifiactions;
    }
}
