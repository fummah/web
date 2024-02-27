import { Typography } from 'antd';
import PropTypes from 'prop-types';

import './loader.css';

const { Text } = Typography;

const ChatBox = ({mymessage,time}) => (
    <div className="container-chat">  
  <Text>{mymessage}</Text>
  <span className="time-right"><Text type="secondary">{time}</Text></span>
</div>
  
        );
export default ChatBox;

ChatBox.propTypes = {
  mymessage: PropTypes.any,
  time: PropTypes.any,
};
