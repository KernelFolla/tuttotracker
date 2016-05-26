import React from 'react'

const ActivityItem = React.createClass({
    propTypes: {
        data: React.PropTypes.object.isRequired
    },

    getDefaultProps: function () {
        return {};
    },

    getInitialState: function () {
        return {};
    },


    render: function () {
        let data = this.props.data;
        return (
            <div className="activity-item">
                <div className="row">
                    <div className="col-md-4"><span>{data.name}</span></div>
                    <div className="col-md-2">{this.renderClient(data)}</div>
                    <div className="col-md-2">{this.renderDate(data.starts_at)}</div>
                    <div className="col-md-2">{this.renderDate(data.ends_at)}</div>
                    <div className="col-md-2">{this.renderDuration(data.starts_at, data.ends_at)}</div>
                </div>
            </div>
        );
    },
    renderClient: function (data) {
        if (data.client) {
            return (<span className="label-client label label-danger">{data.client.name}</span>);
        } else {
            return (<span className="no-client">-</span>);
        }
    },
    renderDate: function (date) {
        if (date) {
            let d = new Date(date);
            d = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear() + " " +
                d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
            return (<span className="date">{d}</span>);
        } else {
            return (<span className="no-date">-</span>);
        }
    },
    renderDuration: function (from, to) {
        if (from && to) {
            let dif = this.getFormattedTime((new Date(to)).getTime() - (new Date(from)).getTime());

            return (<span className="duration">{dif}</span>);
        } else {
            return (<span className="no-duration">-</span>);
        }
    },

    getFormattedTime: function (milliseconds) {
        if (this.props.formatFunc) {
            return this.props.formatFunc(milliseconds);
        }

        var totalSeconds = Math.round(milliseconds / 1000);

        var seconds = parseInt(totalSeconds % 60, 10);
        var minutes = parseInt(totalSeconds / 60, 10) % 60;
        var hours = parseInt(totalSeconds / 3600, 10);

        seconds = seconds < 10 ? '0' + seconds : seconds;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        hours = hours < 10 ? '0' + hours : hours;

        return hours + ':' + minutes + ':' + seconds;
    },

});

export default ActivityItem;