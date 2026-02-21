namespace app.Models
{
    public class Logs_Inventory
    {
        public int Id { get; set; }
        public required string CurrentInventory  { get; set; }
        public DateTime DataInventoryCreated { get; set; } = DateTime.Now;
    }
}
