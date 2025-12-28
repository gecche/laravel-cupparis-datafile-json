import {userApp} from '../stores/userApp';

export default () => {
    return {
        cRef: 'importComune',
        modelName: 'dcomune',
        providerName: 'dcomune',
        loadDatafileRouteName: 'load_datafile_json',
        saveDatafileRouteName: 'save_datafile_json',
        confUpload: {
            value: null,
            name: "resource",
            maxFileSize: "2M",
            modelName: 'dcomune',
            extensions: [
                "csv"
            ],
            ajaxFields: {
                resource_type: "attachment",
                field: 'resource',
            },
        },

        viewUpload: {
            modelName: 'dcomune',
            fields: [

            ],
            fieldsConfig: {

            }
        },
        viewList: {
            modelName: 'dcomune',
            type : 'v-datafile-list',
            fields: [
                'datafile_sheet',
                'row',
                "GTComIstat",
                "GTComDes",
                "GTComPrv",
                "GTComCod",
            ],
            routeName: 'datafile_json_data',
        },

        viewSave: {
            modelName: 'dcomune',
            routeName: null,
            //type : 'v-edit',
            //fields: ['user_id'],
            value : {},
            fieldsConfig: {
                // user_id: {
                //     type: 'w-input',
                //     //inputType : 'hidden',
                //     ready() {
                //         let that = this;
                //         console.debug('userInfo',userApp().userInfo)
                //         userApp().getUserInfo().then(() => {
                //             that.value = userApp().userInfo.id;
                //         })
                //     }
                // },
            }
        },
        listData: {
            type : 'v-datafile-list',
            modelName: 'dcomune',
            fields: [
                // 'prezzario_id'
            ],
            fieldsConfig: {
                // prezzario_id: {
                //     type: 'w-select',
                // }
            }
        },
        // "importDescHtml": "headerImportAziende.html",
        // listData: {
        //
        //     // fields: [
        //     //     'vettore_id'
        //     // ],
        //     // fieldsConfig: {
        //     //     vettore_id: {
        //     //         type: 'w-select',
        //     //     }
        //     // }
        // }
    }
}
