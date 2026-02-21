namespace app.Models
{
    public class Logs_Config
    {
        public int Id { get; set; }
        public required string ItemsQuantity  { get; set; }
        public required string DistributionType  { get; set; }
        public string? TimeInterval  { get; set; }
        public string? SpeedDistribution  { get; set; }
        public string? DividedDay  { get; set; }
        public int? DistributedInTen  { get; set; }
        public DateTime DataLogCreated { get; set; } = DateTime.Now;
    }
}
