import React from 'react';
import PropTypes from 'prop-types';
import { Alert, Space } from 'antd';

const SuccessAlert = ({mymessage}) => (
    <Space
    direction="vertical"
    style={{
      width: '100%',
      textAlign:'center',
      marginBottom:15,
    }}
  >
    <Alert message={mymessage} type="success" showIcon />
    </Space>
        );

SuccessAlert.propTypes = {
  mymessage: PropTypes.any,
};
export default SuccessAlert;