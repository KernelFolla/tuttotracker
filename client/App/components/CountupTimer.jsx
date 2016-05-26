import React from 'react'

// Generic Countup Timer UI component
//
// https://github.com/uken/react-countdown-timer
//
// props:
//   - initialTimePassed: Number
//       The time passed from the start (in ms).
//
//   - interval: Number (optional -- default: 1000ms)
//       The time between timer ticks (in ms).
//
//   - formatFunc(timePassed): Function (optional)
//       A function that formats the timePassed.
//
//   - tickCallback(timePassed): Function (optional)
//       A function to call each tick.
//
//
var CountupTimer = React.createClass({
    displayName: 'CountupTimer',

    propTypes: {
        initialTimePassed: React.PropTypes.number.isRequired,
        interval: React.PropTypes.number,
        formatFunc: React.PropTypes.func,
        tickCallback: React.PropTypes.func,
    },

    getDefaultProps: function() {
        return {
            interval: 1000,
            formatFunc: null,
            tickCallback: null
        };
    },

    getInitialState: function() {
        // Normally an anti-pattern to use this.props in getInitialState,
        // but these are all initializations (not an anti-pattern).
        return {
            timePassed: this.props.initialTimePassed,
            timeoutId: null,
            prevTime: null
        };
    },

    componentDidMount: function() {
        this.tick();
    },

    componentWillReceiveProps: function(newProps) {
        if (this.state.timeoutId) { clearTimeout(this.state.timeoutId); }
        this.setState({prevTime: null, timePassed: newProps.initialTimePassed});
    },

    componentDidUpdate: function() {
        if ((!this.state.prevTime) && this.state.timePassed > 0 && this.isMounted()) {
            this.tick();
        }
    },

    componentWillUnmount: function() {
        clearTimeout(this.state.timeoutId);
    },

    tick: function() {
        var currentTime = Date.now();
        var dt = this.state.prevTime ? (currentTime - this.state.prevTime) : 0;
        var interval = this.props.interval;

        // correct for small variations in actual timeout time
        var timePassedInInterval = (interval - (dt % interval));
        var timeout = timePassedInInterval;

        if (timePassedInInterval < (interval / 2.0)) {
            timeout += interval;
        }

        var timePassed = Math.max(this.state.timePassed + dt, 0);
        
        if (this.isMounted()) {

            if (this.state.timeoutId) { clearTimeout(this.state.timeoutId); }
            this.setState({
                timeoutId: setTimeout(this.tick, timeout),
                prevTime: currentTime,
                timePassed: timePassed
            });
        }


        if (this.props.tickCallback) {
            this.props.tickCallback(timePassed, this.getFormattedTime(timePassed));
        }
    },

    getFormattedTime: function(milliseconds) {
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

    render: function() {
        var timePassed = this.state.timePassed;

        return (
            <input value={this.getFormattedTime(timePassed)} placeholder="0 sec" className="form-control" type="text"
               readOnly/>
        );
    }
});

module.exports = CountupTimer;