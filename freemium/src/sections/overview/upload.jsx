import React from 'react';
import { Form,Space,Upload,message} from 'antd';
import { InboxOutlined } from '@ant-design/icons';

import Scanner from './scanner';

const formItemLayout = {
  labelCol: {
    span: 6,
  },
  wrapperCol: {
    span: 14,
  },
};
const normFile = (e) => {
  console.log('Upload event:', e);
  if (Array.isArray(e)) {
    return e;
  }
  return e?.fileList;
};
const beforeUpload = (file) => {
  const isPDF = file.type === 'application/pdf';
  if (!isPDF) {
    message.error('You can only upload PDF files!');
  }
  return isPDF;
};
const onFinish = (values) => {
  console.log('Received values of form: ', values);
};
const FormUpload = () => (
  <Form
    name="validate_other"
    {...formItemLayout}
    onFinish={onFinish}
    initialValues={{
      'input-number': 3,
      'checkbox-group': ['A', 'B'],
      rate: 3.5,
      'color-picker': null,
    }}
    style={{
      maxWidth: '100%',
      marginTop:20,
    }}
  >
   
    <Form.Item>
      <Form.Item name="dragger" valuePropName="fileList" getValueFromEvent={normFile} noStyle>
        <Upload.Dragger accept=".pdf" name="file" action="https://medclaimassist.co.za/testing/test1.php" beforeUpload={beforeUpload}>
          <p className="ant-upload-drag-icon">
            <InboxOutlined />
          </p>
          <p className="ant-upload-text">No Gueses found.</p>
          <p className="ant-upload-hint">Click or drag file to this area to upload</p>
        </Upload.Dragger>
      </Form.Item>
    </Form.Item>
    <Form.Item
      wrapperCol={{
        span: 12,
        offset: 6,
      }}
    >
      <Space>
       <Scanner/>
      </Space>
    </Form.Item>
  </Form>
);
export default FormUpload;