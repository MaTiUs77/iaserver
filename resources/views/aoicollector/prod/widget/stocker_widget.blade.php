<style>
    @-webkit-keyframes yellow-fade {
        0% {background: yellow; color:#000000;}
        100% {background: none;color: #ffffff;}
    }
    @keyframes yellow-fade {
        0% {background: yellow; color:#000000;}
        100% {background: none;color: #ffffff;}
    }

    #stocker_box a.panel_highlight {
        -webkit-animation: yellow-fade 1s ease-in 1;
        animation: yellow-fade 1s ease-in 1;
    }

    #stocker_box a.header {
        font-size:16px;
        background-color:#000000;
        color:#FFFFFF;
    }

    #stocker_box a.total {
        font-size:16px;
        background-color:#727272;
        color:#FFFFFF;
    }

    #stocker_box a.config input {
        width: 80px;
    }

    #stocker_box a.config button {
        margin-top: 2px;
    }

    #stocker_box div.panel_trace {

        height:200px;
        overflow:auto;
    }
    #stocker_box div.panel_trace a.panel_saved {
        background-color:#7ed67e;
        color:#FFFFFF;
    }

    #stocker_box div.panel_trace a.panel_empty {
        background-color:#FFFFFF;
        color:#000000;
    }


    #stocker_box div.panel_trace a.panel_item {
        font-size:12px;
        padding:2px;
        margin:0px;
    }

    #stocker_box div.panel_trace a.panel_item:hover {
        background-color: #000000;
        color: #FFFFFF;
    }
</style>


