import React, {Component, PropTypes} from 'react'
import {routerActions} from 'react-router-redux'
import {Link} from 'react-router'
import {connect} from 'react-redux'
import {start, stop} from '../actions/activity'
import CountupTimer from './CountupTimer.jsx'

const mapStateToProps = (state) => {
    return {
        user: state.user,
        adder: state.activity.adder
    };
}

const mapDispatchToProps = (dispatch) => {
    return {
        start: (data) => start(data, dispatch),
        stop: (id) => stop(id, dispatch)
    };
}

class ActivityAddContainer extends Component {
    onClick = (e) => {
        e.preventDefault();
        let act = this.props.adder;
        let started = act.startSuccess;
        if (started) {
            this.props.stop(act.data.id);
            document.title = this.backupTitle;
        } else {
            this.props.start({
                name: this.refs.name.value,
                startsAt: "now",
                client: this.refs.client.value
            });
        }
    }

    renderErrors = (fieldName) => {
        let ret = this.props.adder.errors;
        if (!ret) {
            return;
        }
        let i = 0;
        ret = ret.reduce(
            function (ret, item) {
                if (item.key == fieldName) {
                    ret.push(<li key={i}>{item.message}</li>);
                    i++;
                }
                return ret;
            }, []
        );
        if (ret.length)
            return (
                <div className="alert alert-danger">
                    <ul>{ret}</ul>
                </div>
            );
        else
            return (<span/>);
    }

    render() {
        let act = this.props.adder;
        let started = !!act.startSuccess;
        let label = started ? 'Stop' : 'Start';
        let btnClass = started ? 'stop' : 'started';
        if (act.isFetchingStart || act.isFetchingStop) label = 'Loading...';
        return (
            <div className="container">
                <div className="row">
                    <form className="activity-add">
                        <div className="col-md-5 description-container">
                            <input ref="name" type="text" className="form-control"
                                   placeholder="What are you working on?"
                                   required autofocus/>
                        </div>
                        <div className="col-md-3 client-container">
                            <input ref="client" placeholder="Client" className="form-control" type="text"/>
                        </div>
                        <div className="col-md-2 time-container">
                            {this.countupTimer(started)}
                        </div>
                        <div className="col-md-2 button-container">
                            <button className={'btn btn-block '+btnClass}
                                    onClick={this.onClick}>{label}
                            </button>
                        </div>
                        <span className="clearfix"/>
                    </form>
                </div>
                <div className="row activity-errors">
                    <div className="col-md-5">
                        {this.renderErrors('name')}
                    </div>
                    <div className="col-md-3">
                        {this.renderErrors('startsAt')}
                    </div>
                    <div className="col-md-2">
                        {this.renderErrors('client')}
                    </div>
                    <span className="clearfix"/>
                </div>
            </div>
        )
    }

    countupTimer(started) {
        if (started)
            return <CountupTimer initialTimePassed={0} tickCallback={this.tickCallback}/>
        else
            return <input ref="time" placeholder="00:00:00" className="form-control" type="text"
                          readOnly/>
    }

    tickCallback = (time, txt) => {
        if(!this.backupTitle)
            this.backupTitle = document.title;
        document.title = txt+' » '+this.refs.name.value;
    }
}


const ActivityAdd = connect(
    mapStateToProps,
    mapDispatchToProps
)(ActivityAddContainer)

export default ActivityAdd
