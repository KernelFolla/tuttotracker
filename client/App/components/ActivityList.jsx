import React, {Component, PropTypes} from 'react'
import {routerActions} from 'react-router-redux'
import {Link} from 'react-router'
import {connect} from 'react-redux'
import {signup, login} from '../actions/user'
import ActivityItem from './ActivityItem'

const mapStateToProps = (state) => {
    return {user: state.user,
        items: state.activity.list.items
    };
}

const mapDispatchToProps = (dispatch) => {
    return {
        
    };
}


class ActivityListContainer extends Component {
    render() {
        let items = this.props.items
        return (
            <div className="container">
                <div className="row activity-list">
                        {items.map(item =>
                            <ActivityItem
                                key={item.id}
                                data={item}
                            />
                        )}
                </div>
            </div>
        )
    }
}


const ActivityList = connect(
    mapStateToProps,
    mapDispatchToProps
)(ActivityListContainer)

export default ActivityList
