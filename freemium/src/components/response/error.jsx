import React from 'react';
import PropTypes from 'prop-types';
import { Alert, Space } from 'antd';

const ErrorAlert = ({mymessage}) => ( 
  <Space
    direction="vertical"
    style={{
      width: '100%',
      textAlign:'center',
      marginBottom:15,
    }}
  >
    <Alert message={`Error : ${mymessage}`} type="error" showIcon />
     </Space>
    );

ErrorAlert.propTypes = {
  mymessage: PropTypes.any,
};
export default ErrorAlert;