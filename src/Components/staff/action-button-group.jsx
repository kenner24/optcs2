import { toast } from "react-toastify";
import ButtonGroup from "react-bootstrap/ButtonGroup";
import Button from "react-bootstrap/Button";
import EditStaffModal from "./edit-staff-detail";
import PermissionModal from "./permission-modal";
import { useDeleteStaffMutation } from "../../redux/api/staff/staff-api";

export default function ActionButtonGroup({ id, email, name, refetch, permissions }) {
  const [deleteStaff] = useDeleteStaffMutation();
  const handleDeleteStaff = async (id) => {
    let conf = confirm("Are you sure?");
    if (conf) {
      try {
        const data = { id };
        await deleteStaff(data)
          .unwrap()
          .then((res) => {
            refetch();
            toast(res?.message);
          })
          .catch((err) => {
            toast(err?.data?.message);
          });
      } catch (error) {
        toast(error.message);
      }
    }
  };
  return (
    <ButtonGroup aria-label="Basic example">
      <Button className="btn btn-dark trash_btn" onClick={() => handleDeleteStaff(id)}>
        <i className="fa-solid fa-trash"></i>
      </Button>
            &nbsp;
      <EditStaffModal
        id={id}
        email={email}
        name={name}
        refetch={refetch}
      >
      </EditStaffModal>
            &nbsp;
      <PermissionModal
        id={id}
        permissions={permissions}
      >
      </PermissionModal>
    </ButtonGroup>
  );
}