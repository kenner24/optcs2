import React, { useState } from "react";
import Button from "react-bootstrap/Button";
import Modal from "react-bootstrap/Modal";
import Form from "react-bootstrap/Form";
import Table from "react-bootstrap/Table"
import { toast } from "react-toastify";
import { useAssignPermissionMutation } from "../../redux/api/user/user-api";
import { PermissionConstant } from "../../constants/permission.constant";

function PermissionModal({ id, permissions }) {
  const [AssignPermission] = useAssignPermissionMutation();
  const [show, setShow] = useState(false);
  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);
  const [reportsChecked, setReportsChecked] = useState(permissions.includes(PermissionConstant.REPORTS));
  const [connectorsChecked, setConnectorsChecked] = useState(permissions.includes(PermissionConstant.CONNECTORS));
  const [subAccountChecked, setSubAccountChecked] = useState(permissions.includes(PermissionConstant.SUB_ACCOUNT));


  const handleChange = async (checked, permissionType) => {
    try {
      await AssignPermission({
        "assign_permission": checked,
        "permission_type": permissionType,
        "user_id": id
      })
        .unwrap()
        .then((res) => {

          if (PermissionConstant.CONNECTORS === permissionType) {
            setConnectorsChecked(checked);
          } else if (PermissionConstant.REPORTS === permissionType) {
            setReportsChecked(checked);
          } else if (PermissionConstant.SUB_ACCOUNT === permissionType) {
            setSubAccountChecked(checked);
          }

          toast(res?.message);
        })
        .catch((err) => {
          toast(err?.message);
        })
    } catch (error) {
      toast(error.message || "Something went wrong");
    }
  };

  return (
    <>
      <Button variant="dark" onClick={handleShow}>
        <i className="fa-solid fa-id-badge"></i>
      </Button>
      <Modal show={show} onHide={handleClose}>
        <Modal.Header className="modal_header_custom" closeButton>
          <Modal.Title>Permission</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Table borderless>
            <tbody>
              <tr>
                <td>Reports</td>
                <td>
                  <Form.Check
                    inline
                    type="switch"
                    id="reports"
                    onChange={(event) => handleChange(event.target.checked, PermissionConstant.REPORTS)}
                    checked={reportsChecked}
                  />
                </td>
              </tr>
              <tr>
                <td>Connectors</td>
                <td>
                  <Form.Check
                    inline
                    type="switch"
                    id="connectors"
                    onChange={(event) => handleChange(event.target.checked, PermissionConstant.CONNECTORS)}
                    checked={connectorsChecked}
                  />
                </td>
              </tr>
              <tr>
                <td>Sub Account</td>
                <td>
                  <Form.Check
                    inline
                    type="switch"
                    id="account"
                    onChange={(event) => handleChange(event.target.checked, PermissionConstant.SUB_ACCOUNT)}
                    checked={subAccountChecked}
                  />
                </td>
              </tr>
            </tbody>
          </Table>
        </Modal.Body>
      </Modal>
    </>
  );
}

export default PermissionModal;
