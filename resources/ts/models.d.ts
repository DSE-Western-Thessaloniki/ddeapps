declare namespace App.Models {
    export interface User {
        id: number;
        username: string;
        name: string;
        email: string;
        email_verified_at: string;
        password: string;
        remember_token: string | null;
        created_at: string | null;
        updated_at: string | null;
        active: boolean;
        updated_by: number;
        last_login: Date;
        isAdministrator: boolean;
        roles?: Array<App.Models.Role> | null;
    }

    export interface Role {
        id: number;
        name: string;
        created_at: string | null;
        updated_at: string | null;
        users?: Array<App.Models.User> | null;
    }

    export interface Option {
        id: number;
        name: string;
        value: string;
        created_at: string | null;
        updated_at: string | null;
    }

    export interface DocLogo {
        id: number;
        title: string;
        image: string | null;
        text: string | null;
        active: boolean;
        created_at: string | null;
        updated_at: string | null;
        created_by: number;
        updated_by: number;
        creator?: App.Models.User;
        updater?: App.Models.User;
    }

    export interface Editor {
        id: number;
        title: string;
        address: string;
        name: string;
        telephone: string;
        email: string;
        created_at: string | null;
        updated_at: string | null;
        created_by: number;
        updated_by: number;
        creator?: App.Models.User;
        updater?: App.Models.User;
    }

    export interface ExactCopy {
        id: number;
        title: string;
        text: string;
        active: boolean;
        created_at: string | null;
        updated_at: string | null;
        created_by: number;
        updated_by: number;
        creator?: App.Models.User;
        updater?: App.Models.User;
    }

    export interface MailMerge {
        id: number;
        protocol_num: string;
        logo_id: number;
        date: Date;
        subject: string;
        text: string;
        exact_copy_id: number;
        signature_id: number;
        editor_id: number;
        xlsxdata: JSON;
        mergefields: string[];
        xlsxdata_header: string[];
        ada: string | null;
        files_for_teachers: boolean;
        created_at: string | null;
        updated_at: string | null;
        created_by: number;
        updated_by: number;
        creator: App.Models.User;
        updater: App.Models.User;
        editor: App.Models.Editor;
        logo: App.Models.DocLogo;
        exact_copy: App.Models.ExactCopy;
        signature: App.Models.Signature;
    }

    export interface Recipient {
        id: number;
        name: string;
        code: string;
        link: string;
        created_at: string | null;
        updated_at: string | null;
        created_by: number;
        updated_by: number;
        creator: App.Models.User;
        updater: App.Models.User;
        links?: Array<App.Models.Link> | null;
        linksJson: JSON;
    }

    export interface Signature {
        id: number;
        title: string;
        text: string;
        active: boolean;
        created_at: string | null;
        updated_at: string | null;
        created_by: number;
        updated_by: number;
        creator: App.Models.User;
        updater: App.Models.User;
    }
}
